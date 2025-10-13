import { PrismaClient , EventType} from "../generated/prisma";

const prisma = new PrismaClient();

export const getAllAttendees = async (eventId?: number) => {
    if (!prisma.attendee) {
        throw new Error("Attendee model is not available in Prisma Client");
    }
    
    const whereClause = eventId ? { eventId: eventId } : {};
    
  return prisma.attendee.findMany({
    where: whereClause,
    include: {
      event: true,
      ticket: true
    }
  });
};

export const getAttendeeById = async (id: number) => {
    if (!id) {
        throw new Error("Attendee ID is required");
    }
  return prisma.attendee.findUnique({ 
    where: { id },
    include: {
      event: true,
      ticket: true
    }
  });
};

export const deleteAttendee = async (id: number) => {
  if (!id) {
    throw new Error("Attendee ID is required");
  }
  return prisma.attendee.delete({ where: { id } });
};

// Check if attendee exists by email
export const getAttendeeByEmail = async (email: string) => {
    if (!email) {
        return null;
    }
    return prisma.attendee.findFirst({
        where: { 
            email: email.toLowerCase().trim()
        }
    });
};

// Check if attendee exists by email for a specific event
export const getAttendeeByEmailAndEvent = async (email: string, eventId?: number) => {
    if (!email) {
        return null;
    }
    
    const whereClause: any = {
        email: email.toLowerCase().trim()
    };
    
    // If eventId is provided, check for that specific event
    if (eventId) {
        whereClause.eventId = parseInt(String(eventId), 10);
    }
    
    return prisma.attendee.findFirst({
        where: whereClause
    });
};

// Create attendee with duplicate check
export const createAttendeeWithDuplicateCheck = async (
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

    // Check for duplicate email for the specific event if email is provided
    if (email) {
        // IMPORTANT: For import operations, we should ALWAYS have an eventId
        // If no eventId is provided, this is likely an error in the calling code
        if (!eventId) {
            throw new Error("Event ID is required for duplicate checking");
        }
        
        const existingAttendee = await getAttendeeByEmailAndEvent(email, eventId);
        if (existingAttendee) {
            // Return a specific error object to handle duplicates
            throw new Error(`DUPLICATE_EMAIL: Attendee with email ${email} already exists for this event`);
        }
    }

    return prisma.attendee.create({
        data: {
            firstName,
            lastName,
            eventId,
            ticketId,
            email: email?.toLowerCase().trim(),
            phone,
            company,
            jobTitle,
            country,
            qrCode,
            qrCodeData
        }
    });
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
    qrCodeData?: string,
    checkedInAt?: string
) => {
    if (!id) {
        throw new Error("Attendee ID is required");
    }
    
    const updateData: any = {};
    
    // Only include fields that are provided
    if (firstName !== undefined) updateData.firstName = firstName;
    if (lastName !== undefined) updateData.lastName = lastName;
    if (eventId !== undefined) updateData.eventId = eventId;
    if (ticketId !== undefined) updateData.ticketId = ticketId;
    if (email !== undefined) updateData.email = email;
    if (phone !== undefined) updateData.phone = phone;
    if (company !== undefined) updateData.company = company;
    if (jobTitle !== undefined) updateData.jobTitle = jobTitle;
    if (country !== undefined) updateData.country = country;
    if (qrCode !== undefined) updateData.qrCode = qrCode;
    if (qrCodeData !== undefined) updateData.qrCodeData = qrCodeData;
    if (checkedInAt !== undefined) updateData.checkedInAt = checkedInAt;
    
    return prisma.attendee.update({
        where: { id },
        data: updateData
    });
};
