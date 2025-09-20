import type { Request, Response } from "express";
import { getAllUsers ,createUser ,deleteUser , updateUser } from "../services/user.service";

export const getUsers = async (req: Request, res: Response) => {
  try {
      const users = await getAllUsers();
      if (!users.length) {
          return res.status(404).json({ message: "No users found" });
      }
      res.json(users);
  } catch (error) {
      res.status(500).json({ error: "Failed to fetch users" });
  }
};

export const addUser = async (req: Request, res: Response) => {
   const { name, email, password, role, companyId } = req.body;
  try {
      if (!name || !email || !password || !role || !companyId) {
          return res.status(400).json({ message: "All fields are required" });
      }
      const newUser = await createUser(name, email, password, role, companyId);
      res.status(201).json(newUser);
  } catch (error) {
      res.status(500).json({ error: "Failed to create user" });
  }
};

export const removeUser = async (req: Request, res: Response) => {
  const { id } = req.params;
  try {
      await deleteUser(Number(id));
      res.sendStatus(204);
  } catch (error) {
      res.status(500).json({ error: "Failed to delete user" });
  }
};
export const editUser = async (req: Request, res: Response) => {
  const { id } = req.params;
  const { name, email, password, role, companyId } = req.body;
  try {
      const updatedUser = await updateUser(Number(id), name, email, password, role, companyId);
      res.json(updatedUser);
  } catch (error) {
      res.status(500).json({ error: "Failed to update user" });
  }
};
