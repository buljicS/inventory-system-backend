"use client";
import { Slider, IndexCard } from "@/components";
import styles from "@/styles/Index.module.scss";
import { INDEX_CARDS } from "@/utils/constants";
import { Navigation, Footer } from "@/components";

export default function Home() {
    return (
        <>
            <Navigation />
            <Slider />
            <section className={`${styles.more_information} container`}>
                <div className={styles.more_information_workers}>
                    <IndexCard content={INDEX_CARDS[0]} />
                </div>
                <div className={styles.more_information_employer}>
                    <IndexCard content={INDEX_CARDS[1]} />
                </div>
            </section>
            <Footer />
        </>
    );
}
