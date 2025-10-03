import { PrismaClient , EventType, Prisma } from "../generated/prisma";

const prisma = new PrismaClient();

export const getAllEvents = async () => {
  if (!prisma.event) {
      throw new Error("Event model is not available in Prisma Client");
  } 
  return prisma.event.findMany();
};

export const getEventById = async (id: number) => {
  if (!id || isNaN(id)) {
      throw new Error("Event ID is required and must be a valid number");
  }
  return prisma.event.findUnique({ where: { id } });
}

export const deleteEvent = async (id: number) => {
  if (!id) {
      throw new Error("Event ID is required");
  }
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
  if (!name || !companyId || !description || !type || !startTime || !endTime || !eventDate || !eventEndDate) {
      throw new Error("All required fields must be provided");
  }
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
    if (!id) {
        throw new Error("Event ID is required");
    }
    if (!name || !companyId || !description || !type || !startTime || !endTime || !eventDate || !eventEndDate) {
        throw new Error("All required fields must be provided");
    }
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
