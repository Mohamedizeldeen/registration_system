import type{ Request,Response } from "express";
import { createPayment, getAllPayments, getPaymentById, updatePayment, deletePayment } from "../services/payment.service";

export const getPayments = async (req: Request, res: Response) => {
    try {
        const payments = await getAllPayments();
        res.json(payments);
    } catch (error) {
        console.error("Error fetching payments:", error);
        res.status(500).json({ error: "Internal Server Error" });
    }
};

export const getPayment = async (req: Request, res: Response) => {
    if (!req.params.id || isNaN(parseInt(req.params.id, 10))) {
        return res.status(400).json({ error: "Invalid or missing Payment ID" });
    }
    const id = parseInt(req.params.id, 10);
    try {
        const payment = await getPaymentById(id);
        if (!payment) {
            return res.status(404).json({ error: "Payment not found" });
        }
        res.json(payment);
    } catch (error) {
        console.error("Error fetching payment:", error);
        res.status(500).json({ error: "Internal Server Error" });
    }
};

export const deletePaymentById = async (req: Request, res: Response) => {
    if (!req.params.id || isNaN(parseInt(req.params.id, 10))) {
        return res.status(400).json({ error: "Invalid or missing Payment ID" });
    }
    const id = parseInt(req.params.id, 10);
    try {
        await deletePayment(id);
        res.status(204).send();
    } catch (error) {
        console.error("Error deleting payment:", error);
        res.status(500).json({ error: "Internal Server Error" });
    }
};
export const createNewPayment = async (req: Request, res: Response) => {
    const { attendeeId, eventId, ticketId, amount, currency, transactionId, paymentDate, paymentMethod, status } = req.body;
    try {
        const newPayment = await createPayment({
            attendeeId,
            eventId,
            ticketId,
            amount,
            currency,
            transactionId,
            paymentDate,
            paymentMethod,
            status
        });
        res.status(201).json(newPayment);
    } catch (error) {
        console.error("Error creating payment:", error);
        res.status(500).json({ error: "Internal Server Error" });
    }
};

export const updatePaymentById = async (req: Request, res: Response) => {
    if (!req.params.id || isNaN(parseInt(req.params.id, 10))) {
        return res.status(400).json({ error: "Invalid or missing Payment ID" });
    }
    const id = parseInt(req.params.id, 10);
    const { attendeeId, eventId, ticketId, amount, currency, transactionId, paymentDate, paymentMethod, status } = req.body;
    try {
        const updatedPayment = await updatePayment(id, {
            attendeeId,
            eventId,
            ticketId,
            amount,
            currency,
            transactionId,
            paymentDate,
            paymentMethod,
            status
        });
        res.json(updatedPayment);
    } catch (error) {
        console.error("Error updating payment:", error);
        res.status(500).json({ error: "Internal Server Error" });
    }
};
