import { StaticImageData } from "next/image";

export type TInputs = {
    id: number;
    label: string;
    name: string;
    type: string;
    placeholder: string | null;
    multiple?: boolean;
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
    link: string | null;
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
    id: number;
    role: string;
    exp: string;
};

export type TUserData = {
    id: string;
    role: string;
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

export type TPasswordProfileData = {
    currentPassword: string;
    newPassword: string;
    repeatNewPassword: string;
};

export type TItem = {
    id: number;
    itemName: string;
};

export type TRooms = {
    roomName: string;
    roomNumber: number;
    roomDescription: string;
    roomInventoryActive: boolean;
};

export type TTableColumns = {
    id: number;
    field: string;
    header: string;
    sortable?: boolean;
};

export type TRoomsData = {
    roomNumber: number;
    roomName: string;
    roomDescription: string;
};

export type TAddRoomsInputs = {
    id: number;
    name: string;
    label: string;
    type: string;
    placeholder: string;
};

export type TItems = {
    itemName: string;
    itemQuantity: number;
    itemCountryOrigin: string;
};
