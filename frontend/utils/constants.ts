import {
    TFooterSocialMedias,
    TIndexCards,
    TNavFooterLinks,
    TInputs,
    TSlides,
    TSideBarLinks,
    TTableColumns,
    TAddRoomsInputs,
    TApiEndpoints,
} from "./types";
import {
    FacebookIcon,
    YoutubeIcon,
    XIcon,
    InstagramIcon,
    MailIcon,
    TeamIcon,
    ItemIcon,
    DashboardIcon,
    ArchiveIcon,
    TodoIcon,
    RoomIcon,
} from "@/resources/icons";
import { z } from "zod";
import { SliderImage1, SliderImage2 } from "@/resources/images";

export const API_ENDPOINT: TApiEndpoints = {
    LOGIN: "Users/LoginUser",
    REGISTER: "Users/RegisterUser",
    FORGOT_PASSWORD: "Users/ResetPassword",
    FORGOT_PASSWORD_SEND_MAIL: "Users/SendPasswordResetEmail",
};

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
            /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%#?&])[A-Za-z\d@$!%*#?&]{8,}$/,
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
                /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%#?&])[A-Za-z\d@$!%*#?&]{8,}$/,
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
                /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%#?&])[A-Za-z\d@$!%*#?&]{8,}$/,
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
    phoneNumber: z.string().regex(/^\+(?:\d\s?){10,14}\d$/, {
        message: "Phone number must start with a '+' symbol.",
    }),
    company: z.array(z.string()).min(1, {
        message: "At least one company must be selected.",
    }),
});

export const PASSWORD_PROFILE_SCHEMA = z
    .object({
        oldPassword: z
            .string()
            .regex(
                /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%#?&])[A-Za-z\d@$!%*#?&]{8,}$/,
                {
                    message:
                        "Your password must contain minimum eight characters, at least one uppercase letter, one lowercase letter, one number and one special character.",
                }
            ),
        newPassword: z
            .string()
            .regex(
                /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%#?&])[A-Za-z\d@$!%*#?&]{8,}$/,
                {
                    message:
                        "Your password must contain minimum eight characters, at least one uppercase letter, one lowercase letter, one number and one special character.",
                }
            ),
        repeatNewPassword: z
            .string()
            .regex(
                /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%#?&])[A-Za-z\d@$!%*#?&]{8,}$/,
                {
                    message:
                        "Your password must contain minimum eight characters, at least one uppercase letter, one lowercase letter, one number and one special character.",
                }
            ),
    })
    .refine((data) => data.newPassword === data.repeatNewPassword, {
        message: "Passwords don't match.",
        path: ["repeatNewPassword"],
    });

export const ADD_ROOMS_SCHEMA = z.object({
    roomNumber: z.string().min(1, { message: "Room number is required." }),
    roomName: z
        .string()
        .min(5, { message: "Room name must have at least 5 characters." }),
    roomDescription: z.string().min(20, {
        message: "Room description must have at least 20 characters.",
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
        name: "phoneNumber",
        label: "Phone number",
        type: "text",
        placeholder: "Your phone number",
    },

    {
        id: 2,
        name: "company",
        label: "Company",
        type: "select",
        placeholder: null,
        multiple: true,
    },
];

export const PROFILE_PASSWORD_INPUTS: TInputs[] = [
    {
        id: 1,
        name: "oldPassword",
        label: "Current password",
        type: "password",
        placeholder: "Your current password",
    },
    {
        id: 2,
        name: "newPassword",
        label: "New password",
        type: "password",
        placeholder: "Enter new password",
    },
    {
        id: 3,
        name: "repeatNewPassword",
        label: "Repeat new password",
        type: "password",
        placeholder: "Repeat new password",
    },
];

export const ROOM_TABLE_COLUMNS: TTableColumns[] = [
    {
        id: 1,
        field: "roomNumber",
        header: "Room number",
        sortable: true,
    },
    {
        id: 2,
        field: "roomName",
        header: "Name",
    },
    {
        id: 3,
        field: "roomDescription",
        header: "Description",
    },
];

export const ADD_ROOM_FORM_INPUTS: TAddRoomsInputs[] = [
    {
        id: 1,
        name: "roomNumber",
        label: "Room number",
        type: "number",
        placeholder: "Room number",
    },
    {
        id: 2,
        name: "roomName",
        label: "Room name",
        type: "text",
        placeholder: "Room name",
    },
    {
        id: 3,
        name: "roomDescription",
        label: "Room description",
        type: "textarea",
        placeholder: "Room description",
    },
];

export const ITEM_TABLE_COLUMNS: TTableColumns[] = [
    {
        id: 1,
        field: "itemName",
        header: "Item name",
        sortable: true,
    },
    {
        id: 2,
        field: "itemQuantity",
        header: "Quantity",
        sortable: false,
    },
    {
        id: 3,
        field: "itemCountryOrigin",
        header: "Origin of country",
        sortable: false,
    },
];

export const MAX_PROFILE_IMAGE_SIZE: number = 2 * 1204 * 1024;
export const VALID_IMAGE_TYPES: string[] = ["image/jpeg", "image/png"];
