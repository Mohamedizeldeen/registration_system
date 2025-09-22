import type { Request, Response, NextFunction } from "express";
import { type User , Role } from "../generated/prisma";

// Extend Express Request to include user
declare module "express-serve-static-core" {
  interface Request {
    user?: User;
  }
}

export function isAdminOrSuperAdmin(req: Request, res: Response, next: NextFunction) {
    const user = req.user;
    if (!user || (user.role !== Role.super_admin && user.role !== Role.admin)) {
        return res.status(403).json({ message: "Access denied: Admins only" });
    }
    next();
}