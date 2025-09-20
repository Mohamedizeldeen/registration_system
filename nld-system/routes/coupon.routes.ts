import { Router } from "express";
import { getCoupons, createNewCoupon, getCoupon, removeCoupon, modifyCoupon } from "../controllers/coupon.Controller";

const router = Router();

router.get("/", getCoupons);
router.post("/", createNewCoupon);
router.get("/:id", getCoupon);
router.delete("/:id", removeCoupon);
router.put("/:id", modifyCoupon);
export default router;