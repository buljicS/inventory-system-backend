import { StaticImageData } from "next/image";

export type TRegisterLoginInputs = {
    id: number;
    label: string;
    name: string;
    type: string;
    placeholder: string;
};

export type TRegisterData = {
    fname: string;
    lname: string;
    email: string;
    password: string;
    repeatPassword: string;
    phoneNumber: string;
};

export type TLoginData = {
    email: string;
    password: string;
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
