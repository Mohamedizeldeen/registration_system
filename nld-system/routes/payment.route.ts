import { Router } from "express";
import { getPayments, getPayment, createNewPayment, deletePaymentById,updatePaymentById } from "../controllers/payment.controller";
const router = Router();

router.get("/", getPayments);
router.get("/:id", getPayment);
router.post("/", createNewPayment);
router.put("/:id", updatePaymentById);
router.delete("/:id", deletePaymentById);

export default router;