import type { Request, Response } from "express";
import { getAllEvents, createEvent, getEventById, deleteEvent, updateEvent } from "../services/event.service";

export const getEvents = async (req: Request, res: Response) => {
    try {
        const events = await getAllEvents();
        res.json(events);
    } catch (error) {
        res.status(500).json({ error: "Failed to fetch events" });
    }
};

export const createNewEvent = async (req: Request, res: Response) => {
    try {
        const { name, companyId, description, type, startTime, endTime, eventDate, eventEndDate, bannerUrl, email, phone, location, instagram, facebook, website, linkedin, logo } = req.body;

        if (!name || !companyId || !description || !type || !startTime || !endTime || !eventDate || !eventEndDate) {
            return res.status(400).json({ error: "Missing required fields" });
        }
        const newEvent = await createEvent(name, companyId, description, type, startTime, endTime, eventDate, eventEndDate, bannerUrl, email, phone, location, instagram, facebook, website, linkedin, logo);
        res.status(201).json(newEvent);
    } catch (error) {
        res.status(500).json({ error: "Failed to create event" });
    }
};

export const getEvent = async (req: Request, res: Response) => {
    const { id } = req.params;
    if (!id) {
        return res.status(400).json({ error: "Event ID is required" });
    }
    
    const numericId = Number(id);
    if (isNaN(numericId)) {
        return res.status(400).json({ error: "Event ID must be a valid number" });
    }
    
    try {
        const event = await getEventById(numericId);
        res.json(event);
    } catch (error) {
        res.status(500).json({ error: "Failed to retrieve event" });
    }
};

// Public endpoint for sharing events (no authentication required)
export const getPublicEvent = async (req: Request, res: Response) => {
    const { id } = req.params;
    if (!id) {
        return res.status(400).json({ error: "Event ID is required" });
    }
    
    const numericId = Number(id);
    if (isNaN(numericId)) {
        return res.status(400).json({ error: "Event ID must be a valid number" });
    }
    
    try {
        const event = await getEventById(numericId);
        if (!event) {
            return res.status(404).json({ error: "Event not found" });
        }
        
        // Return only public information (exclude sensitive data)
        const publicEvent = {
            id: event.id,
            name: event.name,
            description: event.description,
            type: event.type,
            startTime: event.startTime,
            endTime: event.endTime,
            eventDate: event.eventDate,
            eventEndDate: event.eventEndDate,
            bannerUrl: event.bannerUrl,
            logo: event.logo,
            email: event.email,
            phone: event.phone,
            location: event.location,
            facebook: event.facebook,
            instagram: event.instagram,
            linkedin: event.linkedin,
            website: event.website
        };
        
        res.json(publicEvent);
    } catch (error) {
        res.status(500).json({ error: "Failed to retrieve event" });
    }
};

export const removeEvent = async (req: Request, res: Response) => {
    const { id } = req.params;
    await deleteEvent(Number(id));
    res.sendStatus(204);
};

export const modifyEvent = async (req: Request, res: Response) => {
    try {
        const { id } = req.params;
        const { name, companyId, description, type, startTime, endTime, eventDate, eventEndDate, bannerUrl, email, phone, location, instagram, facebook, website, linkedin, logo } = req.body;

        if (!name || !companyId || !description || !type || !startTime || !endTime || !eventDate || !eventEndDate) {
            return res.status(400).json({ error: "Missing required fields" });
        }
        const updatedEvent = await updateEvent(
            Number(id),
            name,
            companyId,
            description,
            type,
            startTime,
            endTime,
            eventDate,
            eventEndDate,
            bannerUrl,
            email,
            phone,
            location,
            instagram,
            facebook,
            website,
            linkedin,
            logo
        );

        res.json(updatedEvent);
    } catch (error) {
        res.status(500).json({ error: "Failed to update event" });
    }
};
