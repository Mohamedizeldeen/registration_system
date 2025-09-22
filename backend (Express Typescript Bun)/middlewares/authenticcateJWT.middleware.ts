import type { Request, Response, NextFunction } from "express";
import jwt from "jsonwebtoken";
import { PrismaClient } from "../generated/prisma";
import { type User } from "../generated/prisma";


const prisma = new PrismaClient();

const JWT_SECRET = process.env.JWT_SECRET;
if (!JWT_SECRET) {
  throw new Error("JWT_SECRET environment variable is required");
}

// Extend Express Request to include user
declare module "express-serve-static-core" {
  interface Request {
    user?: User;
  }
}

export async function authenticateJWT(req: Request, res: Response, next: NextFunction) {
  const authHeader = req.headers.authorization;
  if (!authHeader) return res.status(401).json({ message: "No token provided" });

  const token = authHeader.split(" ")[1];

  // تحقق من القائمة السوداء
  if (isTokenBlacklisted(token)) {
    return res.status(401).json({ message: "Token is blacklisted" });
  }

  try {
    const decoded = jwt.verify(token, JWT_SECRET);
    if (
      typeof decoded !== "object" ||
      !decoded ||
      !("id" in decoded) ||
      typeof (decoded as any).id !== "number"
    ) {
      return res.status(401).json({ message: "Invalid token" });
    }

    const userId = (decoded as any).id as number;
    const user = await prisma.user.findUnique({ where: { id: userId } });
    if (!user) return res.status(401).json({ message: "User not found" });
    req.user = user;
    next();
  } catch {
    res.status(401).json({ message: "Invalid token" });
  }
}