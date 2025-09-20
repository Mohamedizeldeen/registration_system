import { PrismaClient , PaymentStatus } from "../generated/prisma";
const prisma = new PrismaClient();

export const getAllPayments = async () => {
    if (!prisma.payment) {
        throw new Error("Payment model is not available in Prisma Client");
    }
    return await prisma.payment.findMany();
}
export const deletePayment = async (id: number) => {
    if (!id) {
        throw new Error("Payment ID is required");
    }
    return await prisma.payment.delete({ where: { id } });
}

export const getPaymentById = async (id: number) => {
    if (!id) {
        throw new Error("Payment ID is required");
    }
    return await prisma.payment.findUnique({ where: { id } });
}
export const createPayment = async (
    data: {
        attendeeId?: number,
        eventId?: number,
        ticketId?: number,
        amount: number,
        currency?: string,
        transactionId?: string,
        paymentDate?: Date,
        paymentMethod?: string,
        status?: PaymentStatus,
    }
) => {
    if (!data.amount) {
        throw new Error("Payment amount is required");
    }
    return await prisma.payment.create({
        data: {
            attendeeId: data.attendeeId,
            eventId: data.eventId,
            ticketId: data.ticketId,
            amount: data.amount,
            currency: data.currency,
            transactionId: data.transactionId,
            paymentDate: data.paymentDate,
            paymentMethod: data.paymentMethod,
            status: data.status,
        },
    });
}

export const updatePayment = async (
    id: number,
    data: {
        attendeeId?: number,
        eventId?: number,
        ticketId?: number,
        amount?: number,
        currency?: string,
        transactionId?: string,
        paymentDate?: Date,
        paymentMethod?: string,
        status?: PaymentStatus,
    }
) => {
    if (!id) {
        throw new Error("Payment id is required");
    }
    return await prisma.payment.update({
        where: { id },
        data: {
            attendeeId: data.attendeeId,
            eventId: data.eventId,
            ticketId: data.ticketId,
            amount: data.amount,
            currency: data.currency,
            transactionId: data.transactionId,
            paymentDate: data.paymentDate,
            paymentMethod: data.paymentMethod,
            status: data.status,
        },
    });
}