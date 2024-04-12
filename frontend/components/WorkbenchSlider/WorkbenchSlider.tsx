import styles from "./WorkbenchSlider.module.scss";
import { Swiper, SwiperSlide } from "swiper/react";
import { Autoplay, Navigation, Pagination } from "swiper/modules";
import "swiper/css";
import "swiper/css/pagination";
import "swiper/css/navigation";
import "@/styles/globals.scss";

const WorkbenchSlider = () => {
    return (
        <Swiper
            className="workbench_slider"
            loop={true}
            modules={[Pagination, Autoplay, Navigation]}
            autoplay={{ delay: 25000, disableOnInteraction: false }}
            pagination={{ clickable: true }}
            navigation={true}
        >
            <SwiperSlide>
                <div className={styles.slide}>
                    <div className={styles.slide_header}>
                        <h2>Welcome, [Worker's Name]!</h2>
                    </div>
                    <div className={styles.slide_body}>
                        <p>
                            We're thrilled to have you join our inventory
                            management system. As a valued member of our team,
                            you play a crucial role in ensuring the smooth
                            operation of our inventory processes.
                        </p>
                        <p>
                            Here, you'll find all the tools and resources you
                            need to effectively manage inventory, streamline
                            workflows, and contribute to the success of our
                            organization. Whether you're updating stock levels,
                            processing orders, or generating reports, our system
                            is designed to support you every step of the way.
                        </p>
                        <p>
                            Feel free to explore the various features and
                            functionalities available to you. Should you have
                            any questions or need assistance, our support team
                            is always here to help.
                        </p>
                        <p>
                            Thank you for being part of our inventory management
                            family. Let's work together to achieve great things!
                        </p>
                        <p className={styles.upcoming}>
                            In the upcoming slide: Your guide to creating an
                            inventory. Don't miss it!
                        </p>
                        <p className={styles.signature}>
                            Inventory Management Team
                        </p>
                    </div>
                </div>
            </SwiperSlide>
            <SwiperSlide></SwiperSlide>
        </Swiper>
    );
};

export default WorkbenchSlider;
