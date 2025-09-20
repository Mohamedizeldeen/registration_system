import { Router } from "express";
import {addCompany,editCompany,removeCompany,getCompanies,getCompany } from "../controllers/company.Controller";

const router = Router();

router.get("/", getCompanies);
router.post("/", addCompany);
router.get("/:id", getCompany);
router.put("/:id", editCompany);
router.delete("/:id", removeCompany);

export default router;