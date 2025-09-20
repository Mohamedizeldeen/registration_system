import { PrismaClient } from "../generated/prisma";
const prisma = new PrismaClient();

export const getAllCoupons = async () => {
    if (!prisma.coupon) {
        throw new Error("Coupon model is not available in Prisma Client");
    }   
    return await prisma.coupon.findMany();
};

export const deleteCoupon = async (id: number) => {
        if (!id) {
        throw new Error("Coupon ID is required");
    }
    return await prisma.coupon.delete({ where: { id } });
};

export const getCouponById = async (id: number) => {
    if (!id) {
        throw new Error("Coupon ID is required");
    }
    return await prisma.coupon.findUnique({ where: { id } });
};

export const createCoupon = async (
    code: string,
    discount: number,
    expiryDate: Date | null,
    usageCount: number ,
    maxUsage: number | null,
) => {
        if (!code || discount == null || usageCount == null) {
        throw new Error("All fields (code, discount, usageCount) are required");
    }
    return await prisma.coupon.create({
        data: {
            code,
            discount,
            usageCount,
            expiryDate,
            maxUsage
        }
    });
};

export const updateCoupon = async (
    id: number,
    code: string,
    discount: number,
    usageCount: number,
    expiryDate: Date | null,
    maxUsage: number | null
) => {
    if (!id) {
        throw new Error("Coupon ID is required");
    }
    if (!code || discount == null || usageCount == null) {
        throw new Error("All fields (code, discount, usageCount) are required");
    }
    return await prisma.coupon.update({
        where: { id },
        data: {
            code,
            discount,
            usageCount,
            expiryDate,
            maxUsage
        }
    });
};


