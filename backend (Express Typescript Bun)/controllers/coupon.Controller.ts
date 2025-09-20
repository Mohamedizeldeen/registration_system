import type { Request, Response } from "express";
import { getAllCoupons, getCouponById , updateCoupon ,createCoupon , deleteCoupon } from "../services/coupon.service";

export const getCoupons = async (req: Request, res: Response) => {
    try {
        const coupons = await getAllCoupons();
        res.json(coupons);
    } catch (error) {
        res.status(500).json({ error: "Failed to fetch coupons" });
    }
};
export const createNewCoupon = async (req: Request, res: Response) => {
    const { code, discount, expiryDate, usageCount, maxUsage } = req.body;
    try {
        const coupon = await createCoupon(code, discount, expiryDate, usageCount, maxUsage);
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
    const { code, discount, usageCount, expiryDate, maxUsage } = req.body;
    try {
        const updatedCoupon = await updateCoupon(Number(id), code, discount, usageCount, expiryDate, maxUsage);
        res.json(updatedCoupon);
    } catch (error) {
        res.status(500).json({ error: "Failed to update coupon" });
    }
};