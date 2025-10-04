import { PrismaClient } from "../generated/prisma";
import zod from "zod";

const prisma = new PrismaClient();

const eventZoneSchema = zod.object({
    eventId: zod.number().min(1),
    name: zod.string().min(2).max(100),
    capacity: zod.number().min(1)
});

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
    const validation = eventZoneSchema.safeParse({ eventId, name, capacity });
    if (!validation.success) {
        throw new Error("Invalid input");
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
    const validation = eventZoneSchema.safeParse({ eventId, name, capacity });
    if (!validation.success) {
        throw new Error("Invalid input");
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