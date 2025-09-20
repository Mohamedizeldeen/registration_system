import { PrismaClient, Role } from "../generated/prisma";
import bcrypt from "bcryptjs";

const prisma = new PrismaClient();



export const getAllUsers = async () => {
    if (!prisma.user) {
        throw new Error("User model is not available in Prisma Client");
    }
const users = await prisma.user.findMany();
return users;
};

export const createUser = async (
    name: string,
    email: string,
    password: string, 
    role: Role, 
    companyId: number
) => {
    if (!name || !email || !password || !role || !companyId) {
        throw new Error("All fields (name, email, password, role, companyId) are required");
    }
    const hashedPassword = await bcrypt.hash(password, 10);
    const newUser = await prisma.user.create({
        data: { 
            name, 
            email, 
            password: hashedPassword, 
            role, 
            companyId
        },
    });
    return newUser;
};

export const getUserById = async (id: number) => {
    if (!id) {
        throw new Error("User ID is required");
    }
    return prisma.user.findUnique({ where: { id } });
};

export const deleteUser = async (id: number) => {
    if (!id) {
        throw new Error("User ID is required");
    }
    return prisma.user.delete({ where: { id } });
};
export const updateUser = async (
    id: number, 
    name: string,
    email: string,
    password: string,
    role: Role,
    companyId: number
) => {
    if (!id) {
        throw new Error("User ID is required");
    }
    if (!name || !email || !password || !role || !companyId) {
        throw new Error("All fields (name, email, password, role, companyId) are required");
    }
    const hashedPassword = await bcrypt.hash(password, 10);
    return prisma.user.update({
        where: { id },
        data: { name, email, password: hashedPassword, role, companyId }
    });
};
