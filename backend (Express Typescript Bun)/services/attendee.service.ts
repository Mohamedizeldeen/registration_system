import { PrismaClient , EventType} from "../generated/prisma";

const prisma = new PrismaClient();

export const getAllAttendees = async () => {
    if (!prisma.attendee) {
        throw new Error("Attendee model is not available in Prisma Client");
    }
  return prisma.attendee.findMany();
};

export const getAttendeeById = async (id: number) => {
    if (!id) {
        throw new Error("Attendee ID is required");
    }
  return prisma.attendee.findUnique({ where: { id } });
};

export const deleteAttendee = async (id: number) => {
  if (!id) {
    throw new Error("Attendee ID is required");
  }
  return prisma.attendee.delete({ where: { id } });
};

export const createAttendee = async (
    firstName: string,
    lastName: string,
    eventId?: number,
    ticketId?: number,
    email?: string,
    phone?: string,
    company?: string,
    jobTitle?: string,
    country?: string,
    qrCode?: string,
    qrCodeData?: string
) => {
    if (!firstName || !lastName) {
        throw new Error("First name and last name are required");
    }
    return prisma.attendee.create({
        data: {
            firstName,
            lastName,
            eventId,
            ticketId,
            email,
            phone,
            company,
            jobTitle,
            country,
            qrCode,
            qrCodeData
        }
    });
};

export const updateAttendee = async (
    id: number,
    firstName?: string,
    lastName?: string,
    eventId?: number,
    ticketId?: number,
    email?: string,
    phone?: string,
    company?: string,
    jobTitle?: string,
    country?: string,
    qrCode?: string,
    qrCodeData?: string
) => {
    if (!id) {
        throw new Error("Attendee ID is required");
    }
    return prisma.attendee.update({
        where: { id },
        data: {
            firstName,
            lastName,
            eventId,
            ticketId,
            email,
            phone,
            company,
            jobTitle,
            country,
            qrCode,
            qrCodeData
        }
    });
};
