"use client";
import { useState, useEffect } from "react";
import { REGISTER_INPUTS } from "@/utils/constants";
import { TRegisterData } from "@/utils/types";
import { Form, Button } from "react-bootstrap";
import styles from "@/styles/SignUp.module.scss";
import axios from "axios";
import { REGISTER_SCHEMA } from "@/utils/constants";
import { zodResolver } from "@hookform/resolvers/zod";
import { useForm, SubmitHandler } from "react-hook-form";
import Spinner from "react-bootstrap/Spinner";
import { Navigation, Footer } from "@/components";

const Signup = () => {
    const [isLoading, setIsLoading] = useState<boolean>(false);

    const {
        register,
        handleSubmit,
        reset,
        formState: { errors },
    } = useForm<TRegisterData>({ resolver: zodResolver(REGISTER_SCHEMA) });

    const onSubmit: SubmitHandler<TRegisterData> = async (data) => {
        try {
            setIsLoading(true);
            const response = await axios.post("", { data });
        } catch (error) {
            console.log(error);
        } finally {
            setIsLoading(false);
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
                        <Form onSubmit={handleSubmit(onSubmit)}>
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
                </div>
            </section>
            <Footer />
        </>
    );
};

export default Signup;
