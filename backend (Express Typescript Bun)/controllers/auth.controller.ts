import type { Request, Response } from "express";
import { loginService , signOut } from "../services/auth.service";

export async function loginController(req: Request, res: Response) {
  const { email, password } = req.body;
  try {
    const result = await loginService(email, password);
    res.json(result); // هنا سترجع التوكن للمستخدم
  } catch {
    res.status(401).json({ message: "Invalid credentials" });
  }
}

export async function signOutController(req: Request, res: Response) {
  const authHeader = req.headers.authorization;
  if (!authHeader) return res.status(401).json({ message: "No token provided" });

  const token = authHeader.split(" ")[1];
  if (!token) return res.status(401).json({ message: "No token provided" });
  
  const result = await signOut(token);
  res.json(result);
}