import React from "react";
import Layout from "~/Layouts/DashboardLayout";

const Home = ({ user }) => {
    return (
        <Layout title="Home">
            <h1>Welcome</h1>
            <p>Hello {user.name}, welcome to your first Inertia app!</p>
        </Layout>
    );
};

export default Home;
