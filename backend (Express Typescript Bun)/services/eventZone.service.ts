import { PrismaClient } from "../generated/prisma";
const prisma = new PrismaClient();

export const getAllEventZones = async () => {
    if (!prisma.eventZone) {
        throw new Error("EventZone model is not available in Prisma Client");
    }
    return await prisma.eventZone.findMany();
}
export const deleteEventZone = async (id: number) => {
    if (!id) {
        throw new Error("EventZone ID is required");
    }
    return await prisma.eventZone.delete({ where: { id } });
}
export const getEventZoneById = async (id: number) => {
    if (!id) {
        throw new Error("EventZone ID is required");
    }
    return await prisma.eventZone.findUnique({ where: { id } });
}
export const createEventZone = async (
    eventId: number,
    name: string,
    capacity: number
) => {
    if (!eventId || !name || !capacity) {
        throw new Error("All fields (eventId, name, capacity) are required");
    }
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
    if (!id) {
        throw new Error("EventZone ID is required");
    }
    if (!eventId || !name || !capacity) {
        throw new Error("All fields (eventId, name, capacity) are required");
    }
    return await prisma.eventZone.update({
        where: { id },
        data: {
            eventId,
            name,
            capacity
        }
    });
}