import { PrismaClient } from "../generated/prisma";

const prisma = new PrismaClient();

export const getAllCompanies = async () => {
  return prisma.company.findMany();
}

export const createCompany = async (name: string) => {
  return prisma.company.create({
    data: {
      name,
    }
  });
}

export const getCompanyById = async (id: number) => {
  return prisma.company.findUnique({ where: { id } });
}

export const deleteCompany = async (id: number) => {
  return prisma.company.delete({ where: { id } });
}

export const updateCompany = async (id: number, name: string) => {
  return prisma.company.update({
    where: { id },
    data: { name }
  });
}
