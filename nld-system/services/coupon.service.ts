import { PrismaClient } from "../generated/prisma";
const prisma = new PrismaClient();

export const getAllCoupons = async () => {
    return await prisma.coupon.findMany();
};

export const deleteCoupon = async (id: number) => {
    return await prisma.coupon.delete({ where: { id } });
};

export const getCouponById = async (id: number) => {
    return await prisma.coupon.findUnique({ where: { id } });
};

export const createCoupon = async (
    code: string,
    discount: number,
    expiryDate: Date | null,
    usageCount: number ,
    maxUsage: number | null,
) => {
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


