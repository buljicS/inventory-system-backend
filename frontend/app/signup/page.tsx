"use client";
import { useState } from "react";
import { REGISTER_INPUTS } from "@/utils/constants";
import { TRegisterData } from "@/utils/types";
import { Form, Button } from "react-bootstrap";
import styles from "@/styles/SignUp.module.scss";
import axios from "axios";
import { validateRegisterForm } from "@/utils/functions";
import Spinner from "react-bootstrap/Spinner";
import { Navigation, Footer } from "@/components";

const Signup = () => {
    const [userData, setUserData] = useState<TRegisterData>({
        fname: "",
        lname: "",
        email: "",
        password: "",
        repeatPassword: "",
        phoneNumber: "",
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
        <>
            <Navigation />
            <section className={`${styles.sign_up} container`}>
                <div className={styles.sign_up_content}>
                    <div className={styles.sign_up_content_header}>
                        <div className={styles.sign_up_content_header_div}>
                            <h1>Sign up</h1>
                            <h3>Manage all your inventory efficiently</h3>
                            <p>
                                Let's get you all set up so you can verify your
                                personal account and begin setting up your work
                                profile
                            </p>
                        </div>
                    </div>
                    <div className={styles.sign_up_content_form}>
                        <Form onSubmit={handleSubmit}>
                            {REGISTER_INPUTS.map((input) => (
                                <Form.Group
                                    className="mb-4"
                                    controlId={"FormInput " + input.id}
                                    key={input.id}
                                >
                                    <Form.Label>{input.label}</Form.Label>
                                    <Form.Control
                                        type={input.type}
                                        placeholder={input.placeholder}
                                        name={input.name}
                                        value={userData[input.name]}
                                        onChange={handleChange}
                                    />
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
                    </div>
                </div>
            </section>
            <Footer />
        </>
    );
};

export default Signup;
