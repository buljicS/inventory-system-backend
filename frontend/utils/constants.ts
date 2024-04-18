import {
    TFooterSocialMedias,
    TIndexCards,
    TNavFooterLinks,
    TInputs,
    TSlides,
    TSideBarLinks,
} from "./types";
import {
    FacebookIcon,
    YoutubeIcon,
    XIcon,
    InstagramIcon,
    MailIcon,
    TeamIcon,
    RoomIcon,
    ItemIcon,
    DashboardIcon,
    ArchiveIcon,
    TodoIcon,
} from "@/resources/icons";
import { z } from "zod";
import { SliderImage1, SliderImage2 } from "@/resources/images";

export const REGISTER_INPUTS: TInputs[] = [
    {
        id: 1,
        label: "First name",
        name: "firstName",
        type: "text",
        placeholder: "Enter your first name",
    },
    {
        id: 2,
        label: "Last name",
        name: "lastName",
        type: "text",
        placeholder: "Enter your last name",
    },
    {
        id: 3,
        label: "E-mail",
        name: "email",
        type: "email",
        placeholder: "Enter your e-mail",
    },
    {
        id: 4,
        label: "Password",
        name: "password",
        type: "password",
        placeholder: "Enter your password",
    },
    {
        id: 5,
        label: "Repeat your password",
        name: "repeatPassword",
        type: "password",
        placeholder: "Enter your password again",
    },
    {
        id: 6,
        label: "Phone number",
        name: "phoneNumber",
        type: "text",
        placeholder: "Enter your phone number",
    },
];

export const LOGIN_INPUTS: TInputs[] = [
    {
        id: 1,
        label: "E-mail",
        name: "email",
        type: "email",
        placeholder: "Enter your email",
    },
    {
        id: 2,
        label: "Password",
        name: "password",
        type: "password",
        placeholder: "Enter your password",
    },
];

export const CHANGE_PASSWORD_INPUTS: TInputs[] = [
    {
        id: 1,
        label: "New password",
        name: "newPassword",
        type: "password",
        placeholder: "Enter your new password",
    },

    {
        id: 2,
        label: "Repeat new password",
        name: "repeatNewPassword",
        type: "password",
        placeholder: "Repeat your new password",
    },
];

export const FORGOT_PASSWORD_INPUT: TInputs = {
    id: 1,
    label: "E-mail",
    name: "email",
    type: "email",
    placeholder: "Enter your email",
};

export const NAV_LINKS: TNavFooterLinks[] = [
    {
        id: 1,
        label: "Login",
        link: "/login",
    },
    {
        id: 2,
        label: "Sign up",
        link: "/signup",
    },
];

export const FOOTER_SOCIAL_MEDIAS: TFooterSocialMedias[] = [
    {
        id: 1,
        socialMedia: "facebook",
        icon: FacebookIcon,
        link: "#",
    },
    {
        id: 2,
        socialMedia: "instagram",
        icon: InstagramIcon,
        link: "#",
    },
    {
        id: 3,
        socialMedia: "x",
        icon: XIcon,
        link: "#",
    },
    {
        id: 4,
        socialMedia: "youtube",
        icon: YoutubeIcon,
        link: "#",
    },
    {
        id: 5,
        socialMedia: "mail",
        icon: MailIcon,
        link: "#",
    },
];

export const FOOTER_LINKS: TNavFooterLinks[] = [
    {
        id: 1,
        label: "Lorem",
        link: "#",
    },
    {
        id: 2,
        label: "Lorem",
        link: "#",
    },
    {
        id: 3,
        label: "Lorem",
        link: "#",
    },
    {
        id: 4,
        label: "Lorem",
        link: "#",
    },
    {
        id: 5,
        label: "Lorem",
        link: "#",
    },
    {
        id: 6,
        label: "Lorem",
        link: "#",
    },
    {
        id: 7,
        label: "Lorem",
        link: "#",
    },
];

export const SLIDES: TSlides[] = [
    {
        id: 1,
        image: SliderImage1,
        alt: "Warehouse",
        header: "IMS COMPANY PRESENTS",
        text: "Our Inventory Management System is designed to empower you with efficient tools to streamline and optimize your inventory processes.",
    },
    {
        id: 2,
        image: SliderImage2,
        alt: "Warehouse",
        header: "MODERN DASHBOARD",
        text: "This powerful tool is designed to enhance your experience with our system and provide you with greater control over your operations.",
    },
];

export const INDEX_CARDS: TIndexCards[] = [
    {
        id: 1,
        header: "Does your company use this IMS where you work?",
        subheader:
            "Don't worry! Here is a brief guide on how to use our system.",
        steps: [
            "Create an account on our system by clicking on signup",
            "After you have registered, choose the company you work for",
            "Download the application you will use to perform inventory, and that's it!",
        ],
    },
    {
        id: 2,
        header: "Do you want to collaborate with us?",
        text: "If you are an employer and want to use our system to better organize your inventory in the company, all you need to do is contact us via email!",
    },
];

export const LOGIN_SCHEMA = z.object({
    email: z.string().email("Email is not valid."),
    password: z
        .string()
        .regex(
            /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/,
            {
                message:
                    "Your password must contain minimum eight characters, at least one uppercase letter, one lowercase letter, one number and one special character.",
            }
        ),
});

export const REGISTER_SCHEMA = z
    .object({
        firstName: z.string().min(3, {
            message: "First name must have at least three characters.",
        }),
        lastName: z.string().min(3, {
            message: "Last name must have at least three characters.",
        }),
        email: z.string().email("Email is not valid."),
        password: z
            .string()
            .regex(
                /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/,
                {
                    message:
                        "Your password must contain minimum eight characters, at least one uppercase letter, one lowercase letter, one number and one special character.",
                }
            ),
        repeatPassword: z.string(),
        phoneNumber: z.string().regex(/^\+(?:\d\s?){10,14}\d$/, {
            message: "Phone number must start with a '+' symbol.",
        }),
    })
    .refine((data) => data.password === data.repeatPassword, {
        message: "Passwords don't match.",
        path: ["repeatPassword"],
    });

export const FORGOT_PASSWORD_SCHEMA = z.object({
    email: z.string().email("Email is not valid."),
});

export const CHANGE_PASSWORD_SCHEMA = z
    .object({
        newPassword: z
            .string()
            .regex(
                /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/,
                {
                    message:
                        "Your password must contain minimum eight characters, at least one uppercase letter, one lowercase letter, one number and one special character.",
                }
            ),
        repeatNewPassword: z.string(),
    })
    .refine((data) => data.newPassword === data.repeatNewPassword, {
        message: "Passwords don't match.",
        path: ["repeatNewPassword"],
    });

export const PROFILE_INFORMATION_SCHEMA = z.object({
    firstName: z.string().min(3, {
        message: "First name must have at least three characters.",
    }),
    lastName: z.string().min(3, {
        message: "Last name must have at least three characters.",
    }),
    phoneNumber: z.string().regex(/^\+(?:\d\s?){10,14}\d$/, {
        message: "Phone number must start with a '+' symbol.",
    }),
});

export const SIDEBAR_LINKS_EMPLOYER: TSideBarLinks[] = [
    {
        id: 1,
        link: "/dashboard",
        label: "Dashboard",
        icon: DashboardIcon,
    },

    {
        id: 2,
        link: "/dashboard/inventory-items",
        label: "Inventory items",
        icon: ItemIcon,
    },

    {
        id: 3,
        link: "/dashboard/inventory-rooms",
        label: "Inventory rooms",
        icon: RoomIcon,
    },

    {
        id: 4,
        link: "/dashboard/teams",
        label: "Team management",
        icon: TeamIcon,
    },
];

export const SIDEBAR_LINKS_WORKER: TSideBarLinks[] = [
    {
        id: 1,
        link: "/dashboard",
        label: "Dashboard",
        icon: DashboardIcon,
    },
    {
        id: 2,
        link: "/dashboard/tasks",
        label: "My tasks",
        icon: TodoIcon,
    },
    {
        id: 3,
        link: "/dashboard/archive",
        label: "My archive",
        icon: ArchiveIcon,
    },
];

export const PROFILE_FORM_INPUTS: TInputs[] = [
    {
        id: 1,
        name: "firstName",
        label: "First name",
        type: "text",
        placeholder: "Your first name",
    },
    {
        id: 2,
        name: "lastName",
        label: "Last name",
        type: "text",
        placeholder: "Your last name",
    },
    {
        id: 3,
        name: "phoneNumber",
        label: "Phone number",
        type: "text",
        placeholder: "Your phone number",
    },

    {
        id: 4,
        name: "company",
        label: "Company",
        type: "select",
        placeholder: null,
    },
];
