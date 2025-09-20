import type { Request, Response } from "express";
import { getAllCompanies, createCompany ,getCompanyById , deleteCompany , updateCompany } from "../services/company.service";

export const getCompanies = async (req: Request, res: Response) => {
    const companies = await getAllCompanies();
    res.json(companies);
};

export const addCompany = async (req: Request, res: Response) => {
    const { name, address, phone, email } = req.body;
    const newCompany = await createCompany(name, address, phone, email);
    res.status(201).json(newCompany);
};
export const getCompany = async (req: Request, res: Response) => {
    const { id } = req.params;
    const company = await getCompanyById(Number(id));
    res.json(company);
};
export const removeCompany = async (req: Request, res: Response) => {
    const { id } = req.params;
    await deleteCompany(Number(id));
    res.status(204).send();
}
export const editCompany = async (req: Request, res: Response) => {
    const { id } = req.params;
    const { name, address, phone, email } = req.body;
    const updatedCompany = await updateCompany(Number(id), name, address, phone, email);
    res.json(updatedCompany);
};