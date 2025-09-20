import { PrismaClient } from "../generated/prisma";
const prisma = new PrismaClient();

export const getAllEventZones = async () => {
    return await prisma.eventZone.findMany();
}
export const deleteEventZone = async (id: number) => {
    return await prisma.eventZone.delete({ where: { id } });
}
export const getEventZoneById = async (id: number) => {
    return await prisma.eventZone.findUnique({ where: { id } });
}
export const createEventZone = async (
    eventId: number,
    name: string,
    capacity: number
) => {
    return await prisma.eventZone.create({
        data: {
            eventId,
            name,
            capacity
        }
    });
}
export const updateEventZone = async (
    id: number,
    eventId: number,
    name: string,
    capacity: number
) => {
    return await prisma.eventZone.update({
        where: { id },
        data: {
            eventId,
            name,
            capacity
        }
    });
}