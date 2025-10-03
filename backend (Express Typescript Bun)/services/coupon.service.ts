import { PrismaClient } from "../generated/prisma";
const prisma = new PrismaClient();

export const getAllCoupons = async () => {
    if (!prisma.coupon) {
        throw new Error("Coupon model is not available in Prisma Client");
    }   
    const coupons = await prisma.coupon.findMany({
        include: {
            tickets: {
                include: {
                    attendees: true
                }
            }
        }
    });

    // Calculate usage count dynamically from attendees
    return coupons.map(coupon => {
        const usageCount = coupon.tickets.reduce((total, ticket) => {
            return total + ticket.attendees.length;
        }, 0);

        return {
            ...coupon,
            usageCount,
            tickets: undefined // Remove tickets from response to keep it clean
        };
    });
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
    const coupon = await prisma.coupon.findUnique({ 
        where: { id },
        include: {
            tickets: {
                include: {
                    attendees: true
                }
            }
        }
    });

    if (!coupon) {
        return null;
    }

    // Calculate usage count dynamically from attendees
    const usageCount = coupon.tickets.reduce((total, ticket) => {
        return total + ticket.attendees.length;
    }, 0);

    return {
        ...coupon,
        usageCount,
        tickets: undefined // Remove tickets from response to keep it clean
    };
};

export const createCoupon = async (
    code: string,
    discount: number,
    expiryDate: Date | null,
    maxUsage: number | null,
) => {
        if (!code || discount == null) {
        throw new Error("All fields (code, discount) are required");
    }
    const newCoupon = await prisma.coupon.create({
        data: {
            code,
            discount,
            expiryDate,
            maxUsage
        }
    });

    // Return with calculated usageCount (0 for new coupons)
    return {
        ...newCoupon,
        usageCount: 0
    };
};

export const updateCoupon = async (
    id: number,
    code: string,
    discount: number,
    expiryDate: Date | null,
    maxUsage: number | null
) => {
    if (!id) {
        throw new Error("Coupon ID is required");
    }
    if (!code || discount == null) {
        throw new Error("All fields (code, discount) are required");
    }
    const updatedCoupon = await prisma.coupon.update({
        where: { id },
        data: {
            code,
            discount,
            expiryDate,
            maxUsage
        },
        include: {
            tickets: {
                include: {
                    attendees: true
                }
            }
        }
    });

    // Calculate usage count dynamically from attendees
    const usageCount = updatedCoupon.tickets.reduce((total, ticket) => {
        return total + ticket.attendees.length;
    }, 0);

    return {
        ...updatedCoupon,
        usageCount,
        tickets: undefined // Remove tickets from response to keep it clean
    };
};

export const getCouponByCode = async (code: string) => {
    if (!code) {
        throw new Error("Coupon code is required");
    }
    
    const coupon = await prisma.coupon.findFirst({ 
        where: { 
            code: code
        },
        include: {
            tickets: {
                include: {
                    attendees: true
                }
            }
        }
    });

    if (!coupon) {
        return null;
    }

    // Calculate usage count dynamically from attendees
    const usageCount = coupon.tickets.reduce((total: number, ticket: any) => {
        return total + ticket.attendees.length;
    }, 0);

    return {
        ...coupon,
        usageCount,
        tickets: undefined // Remove tickets from response to keep it clean
    };
};