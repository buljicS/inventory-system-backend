import { TRegisterLoginInputs } from "./types";

export const REGISTER_INPUTS: TRegisterLoginInputs[] = [
    {
        id: 1,
        element: "input",
        label: "First name",
        name: "fname",
        type: "text",
        placeholder: "Enter your first name",
        required: true,
    },
    {
        id: 2,
        element: "input",
        label: "Last name",
        name: "lname",
        type: "text",
        placeholder: "Enter your last name",
        required: true,
    },
    {
        id: 3,
        element: "input",
        label: "E-mail",
        name: "email",
        type: "email",
        placeholder: "Enter your e-mail",
        required: true,
    },
    {
        id: 4,
        element: "input",
        label: "Password",
        name: "password",
        type: "password",
        placeholder: "Enter your password",
        required: true,
    },
    {
        id: 5,
        element: "input",
        label: "Repeat your password",
        name: "repeatPassword",
        type: "password",
        placeholder: "Enter your password again",
        required: true,
    },
    {
        id: 6,
        element: "input",
        label: "Phone number",
        name: "phoneNumber",
        type: "text",
        placeholder: "Phone number",
        required: false,
    },
    {
        id: 7,
        element: "select",
        label: "Company",
        name: "company",
        type: "text",
        placeholder: "Phone number",
        required: false,
    },
];

export const LOGIN_INPUTS: TRegisterLoginInputs[] = [
    {
        id: 1,
        element: "input",
        label: "E-mail",
        name: "email",
        type: "email",
        placeholder: "Enter your email",
        required: true,
    },
    {
        id: 2,
        element: "input",
        label: "Password",
        name: "password",
        type: "password",
        placeholder: "Enter your password",
        required: true,
    },
];
