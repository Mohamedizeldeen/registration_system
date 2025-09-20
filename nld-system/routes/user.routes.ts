import { Router } from "express";
import { getUsers,addUser } from "../controllers/user.Controller";

const router = Router();

router.get("/", getUsers);
router.post("/create", addUser);


export default router;