"use client";
import React from "react";
import { Swiper, SwiperSlide } from "swiper/react";
import { Autoplay, Pagination } from "swiper/modules";
import "swiper/css";
import "swiper/css/pagination";
import Image from "next/image";
import styles from "./Slider.module.scss";
import { SLIDES } from "@/utils/constants";
import { ScrollDown } from "@/resources/icons";

const Slider = () => {
    return (
        <Swiper
            className="hero_slider"
            loop={true}
            modules={[Pagination, Autoplay]}
            autoplay={{ delay: 10000, disableOnInteraction: false }}
            pagination={{ clickable: true }}
        >
            {SLIDES.map((item) => (
                <SwiperSlide key={item.id}>
                    <div>
                        <Image
                            src={item.image}
                            alt={item.alt}
                            width={0}
                            height={0}
                            sizes="100vw"
                            className="image_full"
                        />
                        <div className={styles.slide_content}>
                            <h1>{item.header}</h1>
                            <p>{item.text}</p>
                            <Image
                                src={ScrollDown}
                                alt="Scroll down"
                                width={50}
                                height={50}
                            />
                        </div>
                    </div>
                </SwiperSlide>
            ))}
        </Swiper>
    );
};

export default Slider;
