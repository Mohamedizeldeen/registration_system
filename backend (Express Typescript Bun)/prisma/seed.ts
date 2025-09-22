import { PrismaClient, Role } from "../generated/prisma";
import bcrypt from "bcryptjs";

const prisma = new PrismaClient();

async function main() {
  // Hash passwords
  const password = await bcrypt.hash("admin123", 10);
  const organizerPassword = await bcrypt.hash("organizer123", 10);
  const superAdminPassword = await bcrypt.hash("superadmin123", 10);

  // Create users
  await prisma.user.createMany({
    data: [
      {
        name: "Admin User",
        email: "admin@example.com",
        password,
        role: Role.admin,
      },
      {
        name: "Organizer User",
        email: "organizer@example.com",
        password: organizerPassword,
        role: Role.organizer,
      },
      {
        name: "Super Admin User",
        email: "superadmin@example.com",
        password: superAdminPassword,
        role: Role.super_admin,
      },
    ],
    skipDuplicates: true,
  });
}

main()
  .then(() => {
    console.log("Seeding finished.");
    return prisma.$disconnect();
  })
  .catch((e) => {
    console.error(e);
    return prisma.$disconnect();
  });