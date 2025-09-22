import { PrismaClient } from "../generated/prisma";
import bcrypt from "bcryptjs";
import jwt from "jsonwebtoken";

const tokenBlacklist: string[] = [];

const prisma = new PrismaClient();
const JWT_SECRET = process.env.JWT_SECRET!;

export async function loginService(email: string, password: string) {
  const user = await prisma.user.findUnique({ where: { email } });
  if (!user) throw new Error("Invalid credentials");

  const valid = await bcrypt.compare(password, user.password);
  if (!valid) throw new Error("Invalid credentials");

  const token = jwt.sign(
    { id: user.id, role: user.role },
    JWT_SECRET,
    { expiresIn: "1d" }
  );

  return { token, user: { id: user.id, email: user.email, role: user.role } };
}

export async function signOut(token: string) {

  tokenBlacklist.push(token);
  return { message: "User signed out successfully" };
}

export function isTokenBlacklisted(token: string): boolean {
  return tokenBlacklist.includes(token);
}