import type { Request, Response } from "express";
import { getAllUsers ,createUser } from "../services/user.service";

export const getUsers = async (req: Request, res: Response) => {
  const users = await getAllUsers();
  if (!users.length) {
    return res.status(404).json({ message: "No users found" });
  }
  res.json(users);
};

export const addUser = async (req: Request, res: Response) => {
  const { name, email, password, role, companyId } = req.body;
  if (!name || !email || !password || !role || !companyId) {
    return res.status(400).json({ message: "All fields are required" });
  }
  const newUser = await createUser(name, email, password, role, companyId);
  res.status(201).json(newUser);
};