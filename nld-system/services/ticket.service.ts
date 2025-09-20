import { PrismaClient, type Ticket } from "../generated/prisma";

const prisma = new PrismaClient();

export const getAllTickets = async () => {
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
    return await prisma.ticket.findUnique({ where: { id } });
};


export const deleteTicket = async (id: number) => {
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
