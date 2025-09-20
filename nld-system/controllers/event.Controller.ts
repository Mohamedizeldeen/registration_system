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
    const event = await getEventById(Number(id));
    res.json(event);
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
