import type { Request, Response } from "express";
import { getAllCoupons, getCouponById , updateCoupon ,createCoupon , deleteCoupon } from "../services/coupon.service";

export const getCoupons = async (req: Request, res: Response) => {
    const coupons = await getAllCoupons();
    res.json(coupons);
};
export const createNewCoupon = async (req: Request, res: Response) => {
    const { code, discount, expiryDate, usageCount, maxUsage } = req.body;
    const coupon = await createCoupon(code, discount, expiryDate, usageCount, maxUsage);
    res.status(201).json(coupon);
};
export const getCoupon = async (req: Request, res: Response) => {
    const { id } = req.params;
    const coupon = await getCouponById(Number(id));
    res.json(coupon);
};
export const removeCoupon = async (req: Request, res: Response) => {
    const { id } = req.params;
    await deleteCoupon(Number(id));
    res.sendStatus(204);
};
export const modifyCoupon = async (req: Request, res: Response) => {
    const { id } = req.params;
    const { code, discount, usageCount, expiryDate, maxUsage } = req.body;
    const updatedCoupon = await updateCoupon(Number(id), code, discount, usageCount, expiryDate, maxUsage);
    res.json(updatedCoupon);
}