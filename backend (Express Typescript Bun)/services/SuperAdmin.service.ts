import { PrismaClient } from "../generated/prisma";

const prisma = new PrismaClient();

export const fetchAllDataForDashboard = async () => {
    try {
        const [companies, events, eventZones, tickets, users, payments] = await Promise.all([
            getAllCompanies(),
            getAllEvents(),
            getAllEventZones(),
            getAllTickets(),
            getAllUsers(),
            getAllPayments(),
        ]);
        return { companies, events, eventZones, tickets, users, payments };
    } catch (error) {
        throw new Error("Error fetching all data");
    }
};

export const getAllCompanies = async () => {
    if (!prisma.company) {
        throw new Error("Company model is not available in Prisma Client");
    }
    return prisma.company.findMany();
};

export const getAllEvents = async () => {
    if (!prisma.event) {
        throw new Error("Event model is not available in Prisma Client");
    }
    return prisma.event.findMany();
};

export const getAllEventZones = async () => {
    if (!prisma.eventZone) {
        throw new Error("EventZone model is not available in Prisma Client");
    }
    return prisma.eventZone.findMany();
};

export const getAllTickets = async () => {
    if (!prisma.ticket) {
        throw new Error("Ticket model is not available in Prisma Client");
    }
    return prisma.ticket.findMany();
};

export const getAllUsers = async () => {
    if (!prisma.user) {
        throw new Error("User model is not available in Prisma Client");
    }
    return prisma.user.findMany();
};

export const getAllPayments = async () => {
    if (!prisma.payment) {
        throw new Error("Payment model is not available in Prisma Client");
    }
    return prisma.payment.findMany();
};

