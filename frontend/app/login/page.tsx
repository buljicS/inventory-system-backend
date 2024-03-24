"use client";
import Image from "next/image";
import { useState, useEffect } from "react";
import { LOGIN_INPUTS } from "@/utils/constants";
import { TLoginData } from "@/utils/types";
import { Form, Button } from "react-bootstrap";
import styles from "@/styles/Login.module.scss";
import axios from "axios";
import { LOGIN_SCHEMA } from "@/utils/constants";
import { zodResolver } from "@hookform/resolvers/zod";
import { useForm, SubmitHandler } from "react-hook-form";
import Spinner from "react-bootstrap/Spinner";
import { LoginImage } from "@/resources/images";
import { Navigation, Footer } from "@/components";
import { useRouter } from "next/navigation";

const test = { email: "26121049@vts.su.ac.rs", password: "Filip123!" };

const Login = () => {
    const [isLoading, setIsLoading] = useState<boolean>(false);
    const {
        register,
        handleSubmit,
        formState: { errors },
    } = useForm<TLoginData>({ resolver: zodResolver(LOGIN_SCHEMA) });

    const router = useRouter();

    const onSubmit: SubmitHandler<TLoginData> = async (data) => {
        try {
            setIsLoading(true);
            const response = await axios.post(
                "http://localhost/inventory-system/api/loginUser",
                data
            );

            if (response.data.status === "200") {
                router.push("/dashboard");
                sessionStorage.setItem("bearer", response.data.token);
            }
        } catch (error) {
            console.log(error);
        } finally {
            setIsLoading(false);
        }
    };

    return (
        <>
            <Navigation />
            <section className={`${styles.login} container`}>
                <div className={styles.form}>
                    <div className={styles.form_header}>
                        <h1>Login</h1>
                        <p>
                            Let's make every login count towards a seamlessly
                            organized inventory. Happy working!
                        </p>
                    </div>
                    <Form onSubmit={handleSubmit(onSubmit)}>
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
                                    // @ts-ignore */
                                    {...register(input.name)}
                                />
                                {errors[input.name] && (
                                    <Form.Text className="text-danger">
                                        {errors[input.name].message}
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
            <Footer />
        </>
    );
};

export default Login;
