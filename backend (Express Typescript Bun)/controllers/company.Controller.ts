import type { Request, Response } from "express";
import { getAllCompanies, createCompany ,getCompanyById , deleteCompany , updateCompany } from "../services/company.service";

export const getCompanies = async (req: Request, res: Response) => {
    try {
        const companies = await getAllCompanies();
        res.json(companies);
    } catch (error) {
        res.status(500).json({ error: "Failed to fetch companies" });
    }
};

export const addCompany = async (req: Request, res: Response) => {
    const { name, address, phone, email } = req.body;
   
    try {
        const newCompany = await createCompany(name, address, phone, email);
        res.status(201).json(newCompany);
    } catch (error) {
        res.status(500).json({ error: "Failed to create company" });
    }
};
export const getCompany = async (req: Request, res: Response) => {
    const { id } = req.params;
    try {
        const company = await getCompanyById(Number(id));
        res.json(company);
    } catch (error) {
        res.status(500).json({ error: "Failed to fetch company" });
    }
};
export const removeCompany = async (req: Request, res: Response) => {
    const { id } = req.params;
    try {
        await deleteCompany(Number(id));
        res.status(204).send();
    } catch (error) {
        res.status(500).json({ error: "Failed to delete company" });
    }
}
export const editCompany = async (req: Request, res: Response) => {
    if (!req.body.name || !req.body.address || !req.body.phone || !req.body.email) {
        return res.status(400).json({ error: "Name, address, phone, and email are required" });
    }
    const { id } = req.params;
    const { name, address, phone, email } = req.body;
    try {
        const updatedCompany = await updateCompany(Number(id), name, address, phone, email);
        res.json(updatedCompany);
    } catch (error) {
        res.status(500).json({ error: "Failed to update company" });
    }
};