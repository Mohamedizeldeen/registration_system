import { PrismaClient } from "../generated/prisma";

const prisma = new PrismaClient();

export const getAllCompanies = async () => {
  if (!prisma.company) {
      throw new Error("Company model is not available in Prisma Client");
  }
  return prisma.company.findMany();
}

export const createCompany = async (name: string, address: string, phone: string, email: string) => {
  if (!name || !address || !phone || !email) {
    throw new Error("All fields (name, address, phone, email) are required");
  }
  return prisma.company.create({
    data: {
      name,
      address,
      phone,
      email
    }
  });
}

export const getCompanyById = async (id: number) => {
  if (!id) {
      throw new Error("Company ID is required");
  }
  return prisma.company.findUnique({ where: { id } });
}

export const deleteCompany = async (id: number) => {
  if (!id) {
      throw new Error("Company ID is required");
  }
  return prisma.company.delete({ where: { id } });
}

export const updateCompany = async (id: number, name: string, address: string, phone: string, email: string) => {
  if (!id) {
      throw new Error("Company ID is required");
  }
  if (!name || !address || !phone || !email) {
    throw new Error("All fields (name, address, phone, email) are required");
  }
  return prisma.company.update({
    where: { id },
    data: { name, address, phone, email }
  });
}
