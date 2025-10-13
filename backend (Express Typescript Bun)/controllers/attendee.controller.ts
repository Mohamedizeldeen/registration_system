import type { Request ,Response } from "express";
import { getAllAttendees,getAttendeeById,deleteAttendee,createAttendee,updateAttendee } from "../services/attendee.service";

export const fetchAllAttendees = async (req: Request, res: Response) => {
  try {
    const eventId = req.query.eventId || req.query.event_id;
    const attendees = await getAllAttendees(eventId ? parseInt(eventId as string) : undefined);
    res.json({ allAttendees : attendees });
  } catch (error) {
    console.error("Error fetching attendees:", error);
    res.status(500).json({ error: "Internal server error" });
  }
};

export const fetchAttendeeById = async (req: Request, res: Response) => {
    if (!req.params.id) {
    return res.status(400).json({ error: "Attendee ID is required" });
  }
    const id: number = parseInt(req.params.id, 10);
  try {
    const attendee = await getAttendeeById(id);
    if (!attendee) {
      return res.status(404).json({ error: "Attendee not found" });
    }
    res.json({ attendee : attendee });
  } catch (error) {
    console.error("Error fetching attendee:", error);
    res.status(500).json({ error: "Internal server error" });
  }
};

export const removeAttendee = async (req: Request, res: Response) => {
  if (!req.params.id) {
    return res.status(400).json({ error: "Attendee ID is required" });
  }
    const id: number = parseInt(req.params.id, 10);
  try {
    await deleteAttendee(id);
    res.status(204).send();
  } catch (error) {
    console.error("Error removing attendee:", error);
    res.status(500).json({ error: "Internal server error" });
  }
};
export const addAttendee = async (req: Request, res: Response) => {
  const {
    firstName,
    lastName,
    eventId,
    ticketId,
    email,
    phone,
    company,
    jobTitle,
    country
  } = req.body;

  try {
    // Debug: Log received data
    console.log('Received attendee data:', req.body);
    console.log('firstName:', firstName, 'lastName:', lastName);
    
    // Validate required fields
    if (!firstName || !lastName) {
      return res.status(400).json({ error: "First name and last name are required" });
    }
    
    if (!email) {
      return res.status(400).json({ error: "Email is required" });
    }
    
    if (!jobTitle) {
      return res.status(400).json({ error: "Job title is required" });
    }
    
    if (!company) {
      return res.status(400).json({ error: "Company is required" });
    }
    
    if (!phone) {
      return res.status(400).json({ error: "Phone number is required" });
    }

    if (!eventId) {
      return res.status(400).json({ error: "Event ID is required" });
    }

    // Convert string IDs to integers or undefined
    const parsedEventId = eventId ? parseInt(eventId, 10) : undefined;
    const parsedTicketId = ticketId ? parseInt(ticketId, 10) : undefined;
    
    console.log("Creating attendee with data:", {
      firstName,
      lastName,
      parsedEventId,
      parsedTicketId,
      email,
      phone,
      company,
      jobTitle,
      country
    });
    
    const newAttendee = await createAttendee(
      firstName,
      lastName,
      parsedEventId,
      parsedTicketId,
      email,
      phone,
      company,
      jobTitle,
      country
    );
    res.status(201).json({ createdAttendee: newAttendee });
  } catch (error: any) {
    console.error("Error adding attendee:", error);
    
    // Provide more specific error messages
    if (error.message) {
      res.status(500).json({ error: error.message });
    } else if (error.code === 'P2002') {
      // Unique constraint violation
      res.status(400).json({ error: "An attendee with this information already exists" });
    } else {
      res.status(500).json({ error: "Internal server error" });
    }
  }
};

export const modifyAttendee = async (req: Request, res: Response) => {
    if (!req.params.id) {
    return res.status(400).json({ error: "Attendee ID is required" });
  }
    const id: number = parseInt(req.params.id, 10);
  const {
    firstName,
    lastName,
    eventId,
    ticketId,
    email,
    phone,
    company,
    jobTitle,
    country
  } = req.body;

  try {
    // Debug: Log received data
    console.log('Updating attendee data:', req.body);
    console.log('firstName:', firstName, 'lastName:', lastName);
    
    // Validate required fields
    if (!firstName || !lastName) {
      return res.status(400).json({ error: "First name and last name are required" });
    }
    
    if (!email) {
      return res.status(400).json({ error: "Email is required" });
    }
    
    if (!jobTitle) {
      return res.status(400).json({ error: "Job title is required" });
    }
    
    if (!company) {
      return res.status(400).json({ error: "Company is required" });
    }
    
    if (!phone) {
      return res.status(400).json({ error: "Phone number is required" });
    }
    // Convert string IDs to integers or undefined
    const parsedEventId = eventId ? parseInt(eventId, 10) : undefined;
    const parsedTicketId = ticketId ? parseInt(ticketId, 10) : undefined;
    
    const updatedAttendee = await updateAttendee(
      id,
      firstName,
      lastName,
      parsedEventId,
      parsedTicketId,
      email,
      phone,
      company,
      jobTitle,
      country
    );
    res.json({ modifiedAttendee: updatedAttendee });
  } catch (error) {
    console.error("Error modifying attendee:", error);
    res.status(500).json({ error: "Internal server error" });
  }
};

// Public registration endpoint (no authentication required)
export const registerAttendee = async (req: Request, res: Response) => {
  const {
    firstName,
    lastName,
    eventId,
    ticketId,
    email,
    phone,
    company,
    jobTitle,
    country
  } = req.body;

  // Validate required fields
  if (!firstName || !lastName) {
    return res.status(400).json({ error: "First name and last name are required" });
  }

  try {
    // Convert string IDs to integers
    const parsedEventId = eventId ? parseInt(eventId, 10) : undefined;
    const parsedTicketId = ticketId ? parseInt(ticketId, 10) : undefined;

    // Generate unique QR code data
    const timestamp = new Date().toISOString();
    const uniqueId = `${parsedEventId}-${parsedTicketId}-${Date.now()}`;
    const qrCodeData = JSON.stringify({
      attendeeId: uniqueId,
      eventId: parsedEventId,
      ticketId: parsedTicketId,
      name: `${firstName} ${lastName}`,
      email: email,
      company: company,
      timestamp: timestamp
    });

    // Create attendee with QR code
    const newAttendee = await createAttendee(
      firstName,
      lastName,
      parsedEventId,
      parsedTicketId,
      email,
      phone,
      company,
      jobTitle,
      country,
      uniqueId, // qrCode
      qrCodeData // qrCodeData
    );

    res.status(201).json({ 
      success: true,
      message: "Registration successful",
      attendee: {
        id: newAttendee.id,
        firstName: newAttendee.firstName,
        lastName: newAttendee.lastName,
        email: newAttendee.email,
        company: newAttendee.company,
        jobTitle: newAttendee.jobTitle,
        qrCode: newAttendee.qrCode,
        qrCodeData: newAttendee.qrCodeData
      }
    });
  } catch (error) {
    console.error("Error registering attendee:", error);
    res.status(500).json({ error: "Registration failed. Please try again." });
  }
};
