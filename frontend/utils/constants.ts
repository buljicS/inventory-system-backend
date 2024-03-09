import {
    TFooterSocialMedias,
    TIndexCards,
    TNavFooterLinks,
    TRegisterLoginInputs,
    TSlides,
} from "./types";
import {
    FacebookIcon,
    YoutubeIcon,
    XIcon,
    InstagramIcon,
    MailIcon,
} from "@/resources/icons";
import { z } from "zod";
import { SliderImage1, SliderImage2 } from "@/resources/images";

export const REGISTER_INPUTS: TRegisterLoginInputs[] = [
    {
        id: 1,
        label: "First name",
        name: "fname",
        type: "text",
        placeholder: "Enter your first name",
    },
    {
        id: 2,
        label: "Last name",
        name: "lname",
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
        placeholder: "Phone number",
    },
];

export const LOGIN_INPUTS: TRegisterLoginInputs[] = [
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
        fname: z.string().min(3, {
            message: "First name must have at least three characters.",
        }),
        lname: z.string().min(3, {
            message: "First name must have at least three characters.",
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
        phoneNumber: z.string().regex(/^(0|\+381)[1-9]\d{8}$/, {
            message: "Phone number is not valid.",
        }),
    })
    .refine((data) => data.password === data.repeatPassword, {
        message: "Passwords don't match",
        path: ["repeatPassword"],
    });
