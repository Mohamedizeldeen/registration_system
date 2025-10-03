import type { Request, Response } from "express";
import { getAllCoupons, getCouponById , updateCoupon ,createCoupon , deleteCoupon, getCouponByCode } from "../services/coupon.service";

export const getCoupons = async (req: Request, res: Response) => {
    try {
        const coupons = await getAllCoupons();
        res.json(coupons);
    } catch (error) {
        res.status(500).json({ error: "Failed to fetch coupons" });
    }
};
export const createNewCoupon = async (req: Request, res: Response) => {
    const { code, discount, expiryDate, maxUsage } = req.body;
    try {
        const coupon = await createCoupon(code, discount, expiryDate, maxUsage);
        res.status(201).json(coupon);
    } catch (error) {
        res.status(500).json({ error: "Failed to create coupon" });
    }
};
export const getCoupon = async (req: Request, res: Response) => {
    const { id } = req.params;
    try {
        const coupon = await getCouponById(Number(id));
        res.json(coupon);
    } catch (error) {
        res.status(500).json({ error: "Failed to fetch coupon" });
    }
};
export const removeCoupon = async (req: Request, res: Response) => {
    const { id } = req.params;
    try {
        await deleteCoupon(Number(id));
        res.sendStatus(204);
    } catch (error) {
        res.status(500).json({ error: "Failed to delete coupon" });
    }
};
export const modifyCoupon = async (req: Request, res: Response) => {
    const { id } = req.params;
    const { code, discount, expiryDate, maxUsage } = req.body;
    try {
        const updatedCoupon = await updateCoupon(Number(id), code, discount, expiryDate, maxUsage);
        res.json(updatedCoupon);
    } catch (error) {
        res.status(500).json({ error: "Failed to update coupon" });
    }
};

// Validate coupon endpoint (public - no auth required)
export const validateCoupon = async (req: Request, res: Response) => {
    const { code } = req.body;
    
    if (!code) {
        return res.status(400).json({ error: "Coupon code is required" });
    }
    
    try {
        const coupon = await getCouponByCode(code.trim());
        
        if (!coupon) {
            return res.status(404).json({ error: "Invalid coupon code" });
        }
        
        // Check if coupon has expired
        if (coupon.expiryDate) {
            const expiryDate = new Date(coupon.expiryDate);
            const now = new Date();
            if (expiryDate < now) {
                return res.status(400).json({ error: "Coupon has expired" });
            }
        }
        
        // Check usage limits
        if (coupon.maxUsage && coupon.usageCount >= coupon.maxUsage) {
            return res.status(400).json({ error: "Coupon usage limit reached" });
        }
        
        // Return valid coupon
        res.json(coupon);
        
    } catch (error) {
        console.error('Error validating coupon:', error);
        res.status(500).json({ error: "Failed to validate coupon" });
    }
};