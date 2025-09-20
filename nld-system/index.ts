import express from "express";
import userRoutes from "./routes/user.routes";
import companyRoutes from "./routes/company.routes";

const app = express();
const port = 3000;

app.use(express.json());
app.use("/users", userRoutes);
app.use("/companies", companyRoutes);

app.listen(port, () => {
  console.log(`Server is running at http://localhost:${port}`);
});