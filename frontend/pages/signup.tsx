"use client";
import { useState } from "react";
import { REGISTER_INPUTS } from "@/utils/constants";
import { TRegisterData } from "@/utils/types";
import { Form, Button } from "react-bootstrap";
import "bootstrap/dist/css/bootstrap.min.css";
import styles from "@/styles/SignUp.module.scss";
import axios from "axios";
import { validateRegisterForm } from "@/utils/functions";
import Spinner from "react-bootstrap/Spinner";

const signup = () => {
    const [userData, setUserData] = useState<TRegisterData>({
        fname: "",
        lname: "",
        email: "",
        password: "",
        repeatPassword: "",
        phoneNumber: "",
        company: "default",
    });
    const [isLoading, setIsLoading] = useState<boolean>(false);
    const [errors, setErrors] = useState({});

    const handleChange = (e) => {
        const { name, value } = e.target;
        const updatedUserData = { ...userData, [name]: value };
        const { errorMessages } = validateRegisterForm(updatedUserData);

        setUserData(updatedUserData);
        setErrors(errorMessages);
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        const { isValid } = validateRegisterForm(userData);
        if (isValid) {
            try {
                setIsLoading(true);
                const response = await axios.post("", { userData });
            } catch (error) {
                console.log(error);
            } finally {
                setIsLoading(false);
            }
        }
    };

    return (
        <section className={`${styles.sign_up} container`}>
            <Form onSubmit={handleSubmit}>
                {REGISTER_INPUTS.map((input) => (
                    <Form.Group
                        className="mb-3"
                        controlId={"FormInput " + input.id}
                        key={input.id}
                    >
                        <Form.Label>{input.label}</Form.Label>
                        {input.element === "input" ? (
                            <Form.Control
                                type={input.type}
                                placeholder={input.placeholder}
                                name={input.name}
                                value={userData[input.name]}
                                onChange={handleChange}
                            />
                        ) : (
                            <Form.Select
                                aria-label="Default select example"
                                name={input.name}
                                value={userData[input.name]}
                                onChange={handleChange}
                            >
                                <option value="default">
                                    Choose a company
                                </option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </Form.Select>
                        )}
                        {errors[input.name] && (
                            <Form.Text className="text-danger">
                                {errors[input.name]}
                            </Form.Text>
                        )}
                    </Form.Group>
                ))}
                <Button type="submit">
                    {" "}
                    {isLoading ? (
                        <Spinner animation="border" size="sm" />
                    ) : (
                        "Submit"
                    )}
                </Button>
            </Form>
        </section>
    );
};

export default signup;
