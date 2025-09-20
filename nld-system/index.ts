import express from "express";
import userRoutes from "./routes/user.routes";
import companyRoutes from "./routes/company.routes";
import eventRoutes from "./routes/event.routes";
import ticketRoutes from "./routes/ticket.routes";
import couponRoutes from "./routes/coupon.routes";
import eventZoneRoutes from "./routes/eventZone.routes";
import attendeeRoutes from "./routes/attendee.route";

const app = express();
const port = 3000;

app.use(express.json());
app.use("/tickets", ticketRoutes);
app.use("/users", userRoutes);
app.use("/coupons", couponRoutes);
app.use("/companies", companyRoutes);
app.use("/events", eventRoutes);
app.use("/event-zones", eventZoneRoutes);
app.use("/attendees", attendeeRoutes);

app.listen(port, () => {
  console.log(`Server is running at http://localhost:${port}`);
});