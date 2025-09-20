import type{ Request , Response } from "express";
import { fetchAllDataForDashboard } from "../services/SuperAdmin.service";

export const DashboardController = async (req: Request, res: Response) => {
    try {
        const data = await fetchAllDataForDashboard();
        res.status(200).json(data);
    } catch (error) {
        res.status(500).json({ message: "Error fetching all data", error });
    }
};

