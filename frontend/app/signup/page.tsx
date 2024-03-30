"use client";

import styles from "@/styles/SignUp.module.scss";
import { Navigation, Footer } from "@/components";
import { SignupForm } from "@/components";

const Signup = () => {
    return (
        <>
            <Navigation />
            <section className={`${styles.sign_up} container`}>
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
                <div className={styles.sign_up_content}>
                    <SignupForm />
                </div>
            </section>
            <Footer />
        </>
    );
};

export default Signup;
