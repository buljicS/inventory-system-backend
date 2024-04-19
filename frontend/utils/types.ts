import { StaticImageData } from "next/image";

export type TInputs = {
    id: number;
    label: string;
    name: string;
    type: string;
    placeholder: string | null;
};

export type TRegisterData = {
    firstName: string;
    lastName: string;
    email: string;
    password: string;
    repeatPassword: string;
    phoneNumber: string;
};

export type TLoginData = {
    email: string;
    password: string;
};

export type TForgotPasswordData = {
    newPassword: string;
    repeatNewPassword: string;
    hash: string | null;
};

export type TNavFooterLinks = {
    id: number;
    label: string;
    link: string;
};

export type TFooterSocialMedias = {
    id: number;
    socialMedia: string;
    icon: StaticImageData;
    link: string;
};

export type TSlides = {
    id: number;
    image: StaticImageData;
    alt: string;
    header: string;
    text: string;
};

export type TIndexCards = {
    id: number;
    header: string;
    subheader?: string;
    text?: string;
    steps?: string[];
};

export type TSideBarLinks = {
    id: number;
    link: string;
    label: string;
    icon: StaticImageData;
};

export type TForgotPasswordState = {
    button: boolean;
    form: boolean;
};

export type TForgotPassword = {
    email: string;
};

export type TJwtUser = {
    user: string;
    role: string;
    jwt: string;
};

export type TWorkbenchCard = {
    id: number;
    icon: StaticImageData;
    title: string;
    description: string;
    type: string;
};

export type TProfileData = {
    fname: string;
    lname: string;
    phoneNumber: string;
    company: string;
};
