import { PrismaClient, type Ticket } from "../generated/prisma";

const prisma = new PrismaClient();

export const getAllTickets = async () => {
    if (!prisma.ticket) {
        throw new Error("Ticket model is not available in Prisma Client");
    }
    return await prisma.ticket.findMany();
};

export const createTicket = async (
    eventId: number | null, 
    eventZoneId: number | null, 
    couponId: number | null,
    name: string | null,
    info: string | null,
    price: number,
    quantity: number, 
) => {
    if (price == null || quantity == null) {
        throw new Error("Price and quantity are required");
    }
    return await prisma.ticket.create(
        { 
            data: 
            { 
                eventId, 
                eventZoneId, 
                couponId, 
                name, 
                info, 
                price, 
                quantity 
            } 
        });
};

export const getTicketById = async (id: number) => {
    if (!id) {
        throw new Error("Ticket ID is required");
    }
    return await prisma.ticket.findUnique({ where: { id } });
};


export const deleteTicket = async (id: number) => {
    if (!id) {
        throw new Error("Ticket ID is required");
    }
    return await prisma.ticket.delete({ where: { id } });
};

export const updateTicket = async (
    id: number,
    eventId: number | null,
    eventZoneId: number | null,
    couponId: number | null,
    name: string | null,
    info: string | null,
    price: number ,
    quantity: number,
) => {
    if (!id) {
        throw new Error("Ticket ID is required");
    }
    if (price == null || quantity == null) {
        throw new Error("Price and quantity are required");
    }
    return await prisma.ticket.update({
        where: { id },
        data: {
            eventId,
            eventZoneId,
            couponId,
            name,
            info,
            price,
            quantity
        }
    });
};
