"use client";
import Image from "next/image";
import { useState } from "react";
import { LOGIN_INPUTS } from "@/utils/constants";
import { TLoginData } from "@/utils/types";
import { Form, Button } from "react-bootstrap";
import styles from "@/styles/Login.module.scss";
import axios from "axios";
import { validateLoginForm } from "@/utils/functions";
import Spinner from "react-bootstrap/Spinner";
import { LoginImage } from "@/resources/images";

const Login = () => {
    const [userData, setUserData] = useState<TLoginData>({
        email: "",
        password: "",
    });
    const [isLoading, setIsLoading] = useState<boolean>(false);
    const [errors, setErrors] = useState({});

    const handleChange = (e) => {
        const { name, value } = e.target;
        const updatedUserData = { ...userData, [name]: value };
        const { errorMessages } = validateLoginForm(updatedUserData);

        setUserData(updatedUserData);
        setErrors(errorMessages);
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        const { isValid } = validateLoginForm(userData);
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
        <section className={`${styles.login} container`}>
            <div className={styles.form}>
                <div className={styles.form_header}>
                    <h1>Login</h1>
                    <p>
                        Let's make every login count towards a seamlessly
                        organized inventory. Happy working!
                    </p>
                </div>
                <Form onSubmit={handleSubmit}>
                    {LOGIN_INPUTS.map((input) => (
                        <Form.Group
                            className="mb-3"
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
                                required={input.required}
                            />
                            {errors[input.name] && (
                                <Form.Text className="text-danger">
                                    {errors[input.name]}
                                </Form.Text>
                            )}
                        </Form.Group>
                    ))}

                    <Button type="submit">
                        {isLoading ? (
                            <Spinner animation="border" size="sm" />
                        ) : (
                            "Submit"
                        )}
                    </Button>
                </Form>
            </div>

            <div className={styles.login_image}>
                <Image
                    alt="Login image"
                    src={LoginImage}
                    width={0}
                    height={0}
                    sizes="100vw"
                    className={styles.image}
                />
            </div>
        </section>
    );
};

export default Login;
