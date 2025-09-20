import { PrismaClient , EventType, Prisma } from "../generated/prisma";

const prisma = new PrismaClient();

export const getAllEvents = async () => {
  return prisma.event.findMany();
};

export const getEventById = async (id: number) => {
  return prisma.event.findUnique({ where: { id } });
}

export const deleteEvent = async (id: number) => {
  return prisma.event.delete({ where: { id } });
}

// ...existing code...
export const createEvent = async (
    name: string, 
    companyId: number,
    description: string,
    type: EventType,
    startTime: Date,
    endTime: Date,
    eventDate: Date,
    eventEndDate: Date, 
    bannerUrl?: string,
    email?: string,
    phone?: string,
    location?: string,
    instagram?: string,
    facebook?: string,
    website?: string,
    linkedin?: string,
    logo?: string 
) => {
  const data: Prisma.EventUncheckedCreateInput = {
    name,
    companyId,
    description,
    type,
    startTime,
    endTime,
    eventDate,
    eventEndDate,
    ...(bannerUrl && { bannerUrl }),
    ...(email && { email }),
    ...(phone && { phone }),
    ...(location && { location }),
    ...(instagram && { instagram }),
    ...(facebook && { facebook }),
    ...(website && { website }),
    ...(linkedin && { linkedin }),
    ...(logo && { logo }),
  };

  return prisma.event.create({
    data
  });
};

export const updateEvent = async (
    id: number,
    name: string,
    companyId: number,
    description: string,
    type: EventType,
    startTime: Date,
    endTime: Date,
    eventDate: Date,
    eventEndDate: Date,
    bannerUrl?: string,
    email?: string,
    phone?: string,
    location?: string,
    instagram?: string,
    facebook?: string,
    website?: string,
    linkedin?: string,
    logo?: string
) => {
    const data: Prisma.EventUncheckedUpdateInput = {
        name,
        companyId,
        description,
        type,
        startTime,
        endTime,
        eventDate,
        eventEndDate,
        ...(bannerUrl && { bannerUrl }),
        ...(email && { email }),
        ...(phone && { phone }),
        ...(location && { location }),
        ...(instagram && { instagram }),
        ...(facebook && { facebook }),
        ...(website && { website }),
        ...(linkedin && { linkedin }),
        ...(logo && { logo }),
    };

    return prisma.event.update({
        where: { id },
        data
    });
};
